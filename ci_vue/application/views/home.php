<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>

    <!-- Styles -->
    <link href="<?php echo base_url(); ?>asset/css/app.css" rel="stylesheet">
    <script src="<?php echo base_url(); ?>asset/js/app.js"></script>
</head>
<body>
    <div>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="http://web.facebook.com/eboominathan" target="_blank">
                        Codeigniter + Vue JS -  <small> By Boominathan</small>
                    </a>
                </div>                
            </div>
        </nav>
        <div id="app">
            <!-- contents starts here  -->
            <div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Form Items</div>

                <div class="panel-body">
                    <h3 v-if="loading" class="text-info">Loading...</h3>
                    <h3 v-if="message" class="text-success">{{ message.text }}</h3>
                    <form id="itemsForm" class="form-horizontal" v-on:submit.prevent="validateBeforeSubmit">
                    
                        <div v-bind:class="{'form-group':true, 'col-md-8':true, 'col-md-offset-2' : true, 'has-error':errors.has('code')}">
                            <label for="code" class="control-label">Code</label>
                            <input type="text" v-validate="'required|numeric'" id="code" v-model="newItems.code" name="code" class="form-control">
                            <span v-show="errors.has('code')" class="text-danger">{{ errors.first('code') }}</span>
                        </div>
                        <div v-bind:class="{'form-group':true, 'col-md-8':true, 'col-md-offset-2' : true, 'has-error':errors.has('name')}">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" v-validate="'required'" id="name" v-model="newItems.name" name="name" class="form-control">
                            <span v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</span>
                        </div>
                        <div class="form-group col-md-8 col-md-offset-2">
                            <button v-if="!onEdit" class="btn btn-primary">Add New</button>
                            <button v-if="onEdit" class="btn btn-primary">Update</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                            <button v-if="onEdit" v-on:click="createNew" class="btn btn-info">Create New</button>
                        </div>
                    </form>
                    <div class="col-md-12">
                        <h3>
                            Items Details
                            <button v-on:click="deleteSelected" class="btn btn-danger pull-right">Delete Selected</button>
                        </h3>
                    </div>
                    <table class="table table-stripped">
                        <tr>
                            <th><input type="checkbox" v-on:click="checkAll" v-model="selectedAll"></th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                        <tr v-for="row in rows">
                            <td><input type="checkbox" v-model="row.selected" v-on:click="checkSelectAll"></td>
                            <td>{{ row.code }}</td>
                            <td>{{ row.name }}</td>
                            <td>
                                <button class="btn btn-primary" v-on:click="edit(row.id)">Edit</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content ends here  -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo base_url(); ?>asset/js/vue/vue.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/js/vee-validate/vee-validate.min.js"></script>
    <script src="<?php echo base_url(); ?>asset/js/axios/axios.min.js"></script>
    <script>
        var apiUrl = '<?php echo base_url();?>';                
    Vue.use(VeeValidate);
    var app = new Vue({
        el: '#app',
        data: {
            newItems: {
                selected: false,
                code: '',
                name: ''
            },
            rows: [],
            onEdit: false,
            selectedAll: false,
            delete: [],
            loading: true,
            message: []
        },
        created() {
            this.getRows()
        },
        methods: {
            validateBeforeSubmit: function() {
                var vm = this
                this.$validator.validateAll().then(function(isValid) {
                    if(!isValid) return;
                    vm.startLoading()
                    var url = apiUrl+'home/insert'
                    var message = 'Items added successfully'

                    if(vm.onEdit) {
                        url = apiUrl+'home/update/'+vm.onEdit
                        message = 'Items Updated successfully'
                    } 

                    axios.post(url,
                    new FormData($('#itemsForm')[0])).then(function(response) {
                        vm.getRows()
                        vm.createNew()
                        vm.showMessage(message)
                        vm.endLoading()                        
                    }).catch(function(e) {
                        
                    })
                });
            },
            getRows: function() {
                axios.get(apiUrl+'home/get_data').then(
                    result => {
                        this.rows = result.data,
                        this.endLoading()
                    }
                );
            },
            createNew: function() {               
                this.onEdit = false
                this.newItems = {
                    selected:false,
                    code:'', 
                    name:''
                }
            },
            edit: function(id) {
                this.onEdit = id
                this.startLoading()
                this.newItems = {
                    selected:false,
                    code:'', 
                    name:''
                }
                axios.get(apiUrl+'home/edit/'+id).then(
                    result => {
                        this.newItems.code = result.data.code,
                        this.newItems.name = result.data.name,
                        this.endLoading()
                    }
                );
            },
            checkAll: function() {
                if(this.selectedAll) {
                    this.selectedAll = true;
                    this.rows.forEach(function(row) {
                        row.selected = true
                    })
                } else {
                    this.selectedAll = false;
                    this.rows.forEach(function(row) {
                        row.selected = false
                    })
                }
            },
            checkSelectAll: function() {
                var check = true;
                this.rows.forEach(function (row) {
                    if (row.selected == false) {
                        check = false;
                    } 
                });
                this.selectedAll = check;
            },
            deleteSelected: function() {
                var conf = confirm("Are you sure to delete?");
                if(!conf) return true;
                var vm = this;
                this.startLoading()
                this.rows.forEach(function(row) {
                    if(row.selected) {
                        vm.delete.push({id:row.id})
                    }
                })
                axios.post(apiUrl+'home/delete/',this.delete).then(function(response) {
                        
                    vm.getRows()
                    vm.selectedAll = false
                    vm.createNew()
                    vm.showMessage('Deleted items successfully')
                    vm.endLoading()
                })
                
            },
            startLoading: function() {
                this.loading = true
            },
            endLoading: function() {
                this.loading = false
            },
            showMessage: function(message, status = 'success') {
                this.message = {text:message, status:status}
                this.removeMessage()
            },
            removeMessage: function() {
                var msg = this
                setTimeout(function() {
                    msg.message = {text:'', status:''}
                }, 5000)
            }
        }
    })
    </script>

</body>
</html>
