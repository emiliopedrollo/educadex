<template>
    <form method="GET" :action="action">
        <h1 class="display-1 text-center">Educadex<span>alpha</span></h1>
        <div class="input-group mb-3">
            <input type="text" class="form-control"
                   name="search" :value="question"
                   :placeholder=i18n.search_placeholder
                   :aria-label=i18n.search_placeholder
                   aria-describedby="button-search">
            <div class="input-group-append">
                <input class="btn btn-outline-secondary" type="submit" id="button-search"
                       :value="i18n.make_question"/>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        data() {
            return {
                i18n: window.i18n
            }
        },
        props: [
            'action', 'question'
        ]
    }
</script>
