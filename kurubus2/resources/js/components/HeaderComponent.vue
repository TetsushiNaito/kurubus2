<template>
    <div>
        <div>
        <digital-clock></digital-clock>
        </div>
        <div>
            <table style="margin-bottom:0px" class="table table-borderless">
            <tbody>
                <tr>
                    <td style="width:50%" class="text-center">
                        <label><b>出発：</b><br />
                            <select ref="depr_pole_menu" @change="changeDeprPole">
                                <option v-for="depr in deprs" :key="depr.name" :value="depr.name">{{depr.name}}</option>
                            </select>
                        </label>
                    </td>
                    <td style="width:50%" class="text-center">
                        <label><b>行き先：</b><br />
                            <select ref="dest_pole_menu" @change="changeDestPole">
                                <option v-for="dest in dests" :key="dest.name" :value="dest.name">{{dest.name}}</option>
                            </select>
                        </label>
                    </td>
                </tr>
            </tbody>
            </table>
            <table style="margin-bottom:0px" class="table table-borderless">
            <tbody>
                <tr>
                    <td style="width:33%; vertical-align:middle" class="text-center">
                        <input type="checkbox" ref="isholiday" @change="changeIsHoliday" value="0">祝日ダイヤ</input>
                    </td>
                    <td style="width:34%; vertical-align:middle" class="text-center">
                        <button class="btn btn-primary btn-lg" @click="searchTimetable">更新</button>
                    </td>
                    <td style="width:33%; vertical-align:middle" class="text-center">
                        <button class="btn btn-primary btn-sm" onclick="document.location='http://localhost/submit';">登録</button>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
        <div class="text-center">
            <component :is="timetable_component" :items="items"></component>
        </div>
    </div>
 </template>
 
 <script>
    export default {
        data: function() {
            return {
                /* hogehoge: 'hogehoge ' + Vue.$cookies.get('depr_poles') + 'hogehoge' */
                /* deprs: [ { name: '日吉駅東口' }, { name: '箕輪町' } , { name: '大倉山駅前' }], */
                /* dests: [ { name: '宮前西町' }, { name: '日大高校正門' }, { name: '港北区総合庁舎前' } ], */
                deprs: this.$cookies.get('depr_poles').split(':').map( function(value) { return JSON.parse( '{"name":"' + value + '"}' ) } ),
                dests: this.$cookies.get('dest_poles').split(':').map( function(value) { return JSON.parse( '{"name":"' + value + '"}' ) } ),
                depr_pole: '',
                dest_pole: '',
                isholiday: "0",
                line_num: "5",
                items: [],
                url: '',
                timetable_component : 'timetable-init-component'
            }
        },
        methods: {
            changeDeprPole: function() {
                const self = this;
                self.depr_pole = this.$refs.depr_pole_menu.value;
            },
            changeDestPole: function() {
                const self = this;
                self.dest_pole = this.$refs.dest_pole_menu.value;
            },
            changeIsHoliday: function() {
                const self = this;
                ( this.$refs.isholiday.checked ) ? self.isholiday = "1" : self.isholiday = "0";
            },
            searchTimetable: function() {
                const self = this;
                self.url = 'http://localhost/' + self.depr_pole + '/' + self.dest_pole + '/' + self.line_num + '/' + self.isholiday;
                self.timetable_component = 'timetable-wait-component';
                //alert ('url: '+self.url)
                this.axios.get(self.url).then((response) => {
                    self.items = response.data;
                    //alert( self.items );
                    if ( self.items == -1 ) {
                        self.timetable_component = 'timetable-sorry-component';
                    } else if (self.items == -11 ) {
                        self.timetable_component = 'timetable-error-component';
                    } else {
                        self.timetable_component = 'timetable-list-component';
                    }
                })
                .catch((e) => {
                    alert(e);
                });
            }
        },
        mounted: function() {
                const self = this;
                self.depr_pole = this.$refs.depr_pole_menu.value;
                self.dest_pole = this.$refs.dest_pole_menu.value;
                self.isholiday = this.$refs.isholiday.value;
                self.url = 'http://localhost/' + self.depr_pole + '/' + self.dest_pole + '/' + self.line_num + '/' + self.isholiday;
        }

    }
 </script>