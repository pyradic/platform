import Vue from 'vue';
import { component, prop } from '@/decorators';

const log = require('debug')('components:script')

@component({})
export class Script extends Vue {
    static template = `<div class="py-script" style="display: none"><slot></slot></div>`
    @prop(String) src:string

    created(){
        this.$nextTick(() => {
            let script = document.createElement('script');
            document.body.append(script);
            if(this.src){
                script.setAttribute('src', this.src);
            } else {
                let content          = this.$el && this.$el.textContent ? this.$el.textContent : null
                this.$el.textContent = '';
                script.textContent = content;
            }
            log('created script', script)

        })
        log('created', this)
    }
}
