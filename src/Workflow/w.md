


Workflow
Has multiple `places`. A `state` can reside in one `place`
Has a `state`. defines the `place` the `state` is in. has `metadata` containing additional data used by `transitions` and `actions`
Has multiple `transitions`. A `transition` moves the `state` from it's `place` to a target `place`.  
A `transaction` can have one or multiple `actions`. The `actions` of the `transaction` will be executed in a fixed order and change the `state` `metadata`
A `transaction` returns a `result`. The result can return a request response,

example:
```php
$workflow = [
    'places' => [
        'unmatched',
        'pick_match',
        'match_picked',
        'matched'
    ],
    'transactions' => [
        'pick_match' => [
            'from' => 'unmatched',
            'to' => 'pick_match',
//            'type' => 'response',
//            'type' => 'call',
            'handler' => null, // redirect to screen
            'screen' => null,  // render screen
        ]       
    ]          
];
```

