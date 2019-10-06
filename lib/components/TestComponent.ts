import { createComponent } from '@vue/composition-api';
import {reactive,ref} from '@vue/composition-api'


export const TestComponent = createComponent({
    props: {
        foo:String
    },
    template: '<div><h1>TestComp: {{ count }} {{ v.bar }}</h1></div>',
    setup(props){

        const v = reactive({
            bar: 'testComp prop: ' + props.foo
        });
        const count = ref(0)
        return { v   , count     }
    }
})