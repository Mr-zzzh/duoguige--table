<template>
    <div class="">
        123123
    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                
            }
        },
        methods:{
            getlist(){
                this.$axios({
                    url:`${this.api}admin/admin`,
                    methods:'get',
                    params:{

                    }
                }).then(res=>{
                    console.log(res)
                })
            }
        },
        created(){
            this.getlist()
        }
    }
</script>

<style lang="less" scoped>
    
</style>