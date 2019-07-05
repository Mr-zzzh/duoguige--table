<template>
    <div>
        123
    </div>
</template>

<script>
    export default {
        name: '',
        data() {
            return {
                id:'',
            }
        },
        methods:{




            getinfo(){
                this.$axios({
                    method:'get',
                    url:`${this.api}admin/news/${this.id}`,
                    params:{

                    }
                }).then(res=>{
                    if(res.data.status == 1){
                        console.log(res.data.data)
                    }
                })
            },
        },
        created(){
            this.id = this.$route.query.id
            console.log(this.bjid == '')
            if(this.bjid != ''){
                this.getinfo()
            }
        }
    }
</script>

<style lang="less" scoped>
    
</style>