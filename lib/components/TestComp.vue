<template>
    <div><h1>TestComp: {{ count }} {{ v.bar }}</h1></div>
</template>

<script lang="ts">
    import Vue from 'vue';
    import { component } from '@/decorators';

    @component()
    export default class TestComp extends Vue {
        mounted(){

        }
    }
</script>