<script>
// 是否启用单一authz文件
let svn_single_authz = true;

export default {
    svn_single_authz,
    methods: {
        /**
         * 获取配置文件信息
         */
        getAuthzConfig() {
            var that = this;
            var configList = [];
            var data = {};
            that.$axios
                .post("api.php?c=Setting&a=GetDirInfo&t=web", data)
                .then(function (response) {
                    var result = response.data;
                    if (result.status == 1) {
                        configList = result.data;
                        that.svn_single_authz = configList.svn_single_authz;
                        console.log({ content: result.data });
                    } else {
                        console.log({ content: result.message });
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    }
}

</script>