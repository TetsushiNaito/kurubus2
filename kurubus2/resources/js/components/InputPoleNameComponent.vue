<template>
<div id="watch-example">
    <p>
        <input v-model="question"> {{ answer }}
    </p>
</div>
</template>

<script>
export default {
	data: function() {
    	return {
    		question: '',
    		answer: 'バス停名を入力してください。'
    	}
  	},
    watch: {
    // この関数は question が変わるごとに実行されます。
        question: function (newQuestion, oldQuestion) {
        this.answer = '入力中...'
        this.debouncedGetAnswer()
        }
    },
    created: function () {
        this.debouncedGetAnswer = _.debounce(this.getAnswer, 500)
    },
    methods: {
        getAnswer: function () {
            this.answer = '確認中...';
            let self = this;
            let baseurl = 'https://api-tokyochallenge.odpt.org/api/v4/';
            let access_token = 'e5f8c0903e7db287cbe3491292f9d6f42d3e204ea8970378cd7f4f48bc335b1e';
            let url = baseurl + 'odpt:BusstopPole?acl:consumerKey=' + access_token + '&dc:title=' + self.question;
            //alert( url );
            axios.get( url, { 
                withCredentials: true, 
                header: {
                    'X-Requested-With': 'XMLHttpRequest'
                    } 
                })
                .then(function (response) {
                    let answer = response.data[0];
                        if ( answer ) {
                            self.answer = "OK";
                        } else {
                            sele.answer = "正しいバス停名を入力してください";
                        }
                })
                .catch(function (error) {
                    self.answer = 'エラー：APIに接続できませんでした ' + error;
	            });
        }
    } 
}  
</script>