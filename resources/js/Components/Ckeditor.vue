<template>
    <ckeditor :editor="editor" v-model="text" :config="editorConfig"></ckeditor>
</template>

<script>
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    export default {
        components: {
        },
        props: {
            value: {}
        },
        data() {
            return {
                text: "",
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                }
            }
        },

        computed: {},

        methods: {
        },

        watch: {
            value: {
                immediate: true,
                handler(value) {
                    this.text = value;
                }
            },

            text(text) {
                this.$emit('input',text);
            }
        },

        mounted() {
        },

    }
</script>
