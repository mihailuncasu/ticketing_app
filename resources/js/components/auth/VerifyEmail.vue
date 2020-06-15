<template>
</template>

<script>
    import api from '@/api/auth';

    export default {
        name: "VerifyEmail",

        created() {
            api.emailVerification(
                this.$route.query
            ).then((response) => {

            }).catch((error) => {

            });
            this.$router.push({name: 'login'});
        }
    }
</script>

<style scoped>

</style>