<?php

namespace Pyro\Platform\Diagnose;

use Illuminate\Console\Command;
use Pyro\Platform\Diagnose\Checks\Check;

class DiagnoseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'diagnose {environment?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform application self diagnosis.';

    private $messages = [];

    public function handle()
    {
        $this->runChecks(config('platform.diagnose.checks', []), trans('platform::diagnose::commands.self_diagnosis.common_checks'));

        $environment = $this->argument('environment', app()->environment());
        $environmentChecks = config('platform.diagnose.environment_checks.' . $environment, []);

        if (empty($environmentChecks) && array_key_exists($environment, config('platform.diagnose.environment_aliases'))) {
            $environment = config('platform.diagnose.environment_aliases.' . $environment);
            $environmentChecks = config('platform.diagnose.environment_checks.' . $environment, []);
        }

        $this->runChecks($environmentChecks, trans('platform::diagnose.commands.self_diagnosis.environment_specific_checks', ['environment' => $environment]));

        if (count($this->messages)) {
            $this->error(trans('platform::diagnose.commands.self_diagnosis.failed_checks'));

            foreach ($this->messages as $message) {
                $this->output->writeln('<fg=red>'.$message.'</fg=red>');
                $this->output->writeln('');
            }
            return 1; // Any other return code then 0 means exit with error
        }
        $this->info(trans('platform::diagnose.commands.self_diagnosis.success'));
        return 0;
    }

    protected function runChecks(array $checks, string $title)
    {
        $max = count($checks);
        $current = 1;

        $this->output->writeln('|-------------------------------------');
        $this->output->writeln('| '.$title);
        $this->output->writeln('|-------------------------------------');

        foreach ($checks as $check => $config) {
            if (is_numeric($check)) {
                $check = $config;
                $config = [];
            }

            $checkClass = app($check);

            $this->output->write(trans('platform::diagnose::commands.self_diagnosis.running_check', [
                'current' => $current,
                'max' => $max,
                'name' => $checkClass->name($config),
            ]));

            $this->runCheck($checkClass, $config);

            $current++;
        }

        $this->output->writeln('');
    }

    protected function runCheck(Check $check, array $config)
    {
        if ($check->check($config)) {
            $this->output->write('<fg=green>✔</fg=green>');
        } else {
            $this->output->write('<fg=red>✘</fg=red>');

            $this->messages[] = $check->message($config);
        }

        $this->output->write(PHP_EOL);
    }
}
