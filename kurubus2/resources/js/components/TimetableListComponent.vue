<template>
     <div class="container">
        <table class="table">
            <thead class="thead-light">
            <tr>
                 <th scope="col">#</th>
                 <th scope="col">発車時刻</th>
                 <th scope="col">系統</th>
             </tr>
             </thead>
             <tbody>
             <tr v-for="item in items" :key="item.id">
                    <th scope="row">{{item.id}}</th>
                    <td>{{item.depr_time}}</td>
                    <td>{{item.route_name}}</td>
             </tr>
             </tbody>
         </table>
     </div>
</template>
 
<script>
//Vue.config.devtools = true;
    export default {
        props: {
            items: Array,
        },            
        data: function() {
            return {
            }
        }
    }
 </script>