<template>
    123
</template>
<script>
    export default {
    data() { return { msg: null } },
        created() {
            window.Echo.channel('my-channel').listen('ArticleCreateEvent', (name) => {
                console.log(name);
                this.msg=name.name;
                // alert('Добавлена новая статья!');
            })
        }
    }
</script>

