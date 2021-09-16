<template>
    <div>
        <h2 class="text-center">{{ clock }}</h2>
    </div>
</template>

<script>
//Vue.config.devtools = true;
    export default {
        data : function() {
            return {
                clock: ''
            }
        },
        methods: {
            DigitalClock: function() {
                let current_time = new Date();
                let hour = current_time.getHours(); // 時
                let min  = current_time.getMinutes(); // 分
                let sec  = current_time.getSeconds(); // 秒

                // 数値が1桁の場合、頭に0を付けて2桁で表示する指定
                if( hour < 10 ) { hour = "0" + hour; }
                if( min < 10 ) { min = "0" + min; }
                if( sec < 10 ) { sec = "0" + sec; }
                let self = this;
                self.clock = hour + ':' + min + ':' + sec;
            }
        },
        mounted: function() {
            let self = this;
            let timerID = setInterval( function(){ self.DigitalClock() }, 1000 );
        }
    }
</script>