<div id="app" class="container">
    <div class="nav" style="margin-top: 1%">
        <div class="col-md-3 col-lg-2 col-sm-3">
            <button class="btn brn-icon"  @click="showNewTask = !showNewTask; preview = false">+</button>
            <select v-model="sortParam" @change="sortedList()">
                <option>User Name</option>
                <option>User Email</option>
            </select>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-6"></div>
        <div class="col-md-2 col-lg-2 col-sm-2">
            <button v-if="!authorize" class="btn btn-default" @click="showAuth = !showAuth" style="float: right">Log
                in
            </button>
        </div>
    </div>
    <section v-if="showNewTask && !preview">
        <div class="login-card">
            <h1>Create a new task</h1><br>
            <div>
                <input type="text" placeholder="Your name" v-model="newTask.name">
                <input type="text" placeholder="Your email" v-model="newTask.email">
                <input type="text" placeholder="Description" v-model="newTask.description">
                Image to upload: <input type="file" name="fileToUpload" id="fileToUpload" @change="onFileChanged">
                <button class="login login-submit" @click="storeTask()">Save</button>
                <div class="login-help">
                    <a @click="preview = !preview;">Preview</a>
                </div>
            </div>

        </div>
    </section>
    <section v-if="preview">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-lg-4"></div>
            <div class="col-md-4 col-sm-6 col-lg-4">
                <div class="card card-big">
                    <div class="card-image"
                         :style="'background-image: url(' + file +');'"></div>
                    <h2 class="card-title">{{newTask.name}}</h2>
                    <p class="card-text">{{newTask.description}}</p>
                    <div class="card-action-bar">
                        <button class="btn btn-info" @click="storeTask">Save</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-lg-4"></div>
        </div>
    </section>
    <section v-if="!showAuth">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-lg-4" v-for="task in tasks">
                <div class="card card-big">
                    <div class="card-image"
                         :style="'background-image: url(../../resources/images/' + task.image +');'"></div>
                    <h2 class="card-title">{{task.user_name}}</h2>
                    <p class="card-text">{{task.description}}</p>
                    <div class="card-action-bar" v-if="user.email == 'admin'">
                        <button class="btn btn-info" v-if="task.status == 0" @click="setStatus(task, 1)">Complete
                        </button>
                        <button class="btn btn-info" v-else @click="setStatus(task, 0)">UnComplete</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 3%">
            <div class="col-md-5 col-lg-5 col-sm-5"></div>
            <div class="col-md-2 col-lg-2 col-sm-2" style="text-align: center">
                <button class="btn btn-default" @click="fetchTasks" v-if="isMore">
                    More
                </button>
            </div>
            <div class="col-md-5 col-lg-5 col-sm-5">
            </div>
        </div>
    </section>

    <section v-else-if="!showNewTask" class="auth-section">
        <div class="login-card">
            <h1>Get access to the tasks</h1><br>
            <div>
                <input type="text" name="name" placeholder="Name" v-if="register" v-model="user.name">
                <input type="text" name="email" placeholder="Email" v-model="user.email">
                <input type="password" name="pass" placeholder="Password" v-model="user.password">
                <button class="login login-submit" @click="loginUser()" v-if="!register">Log In</button>
                <button class="login login-submit" @click="registerUser" v-else>Register</button>
            </div>

            <div class="login-help">
                <a @click="register = !register; login = !login;">Register</a>
            </div>
        </div>
    </section>

</div>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            tasks: [],
            newTask: {name: '', email: '', description: '', image: ''},
            register: false, login: true, authorize: false,
            showAuth: false, showNewTask: false,
            user: {name: '', password: '', email: '', id: ''},
            limit1: 3, limit2: 0, isMore: true, file: '', preview: false, sortParam: 'User Email'
        },
        created()
        {
            this.fetchTasks();
        },
        methods: {
            sortedList () {
                switch(this.sortParam){
                    case 'User Name': return this.tasks.sort(sortByName);
                    case 'User Email': return this.tasks.sort(sortByEmail);
                }
            },
            registerUser(){
                let formData = new FormData();
                formData.append('email', this.user.email);
                formData.append('password', this.user.password);
                formData.append('name', this.user.name);
                axios.post('/auth/register', formData).then(response => {
                    this.authorize = true;
                    this.showAuth = !this.showAuth;

                });
            },
            loginUser(){
                let formData = new FormData();
                formData.append('email', this.user.email);
                formData.append('password', this.user.password);
                axios.post('/auth/login', formData).then(response => {
                    if (response.data != 0) {
                        this.user = response.data;
                        this.authorize = true;
                        this.showAuth = !this.showAuth;
                    }
                    else alert('Wrong data');
                });
            },
            onFileChanged (event) {
                this.newTask.image = event.target.files[0];
                this.file = URL.createObjectURL(event.target.files[0]);
            },
            fetchTasks(){
                let formData = new FormData();
                formData.append('limit_1', this.limit1);
                formData.append('limit_2', this.limit2);
                axios.post('/tasks/show', formData).then(response => {
                    if (this.limit2 == 0) {
                        this.limit1 = 3;
                        this.limit2 = 6;
                    }
                    else {
                        this.limit1 = this.limit2;
                        this.limit2 = this.limit1 + 3;
                    }
                    for (let i = 0; i < response.data.length; i++) {
                        this.tasks.push(response.data[i]);
                    }
                    this.sortedList();
                    if(response.data.length < 3) this.isMore = false

                });
            },
            storeTask(){
                let formData = new FormData();
                formData.append('description', this.newTask.description);
                formData.append('image', this.newTask.image, this.newTask.image.name);
                formData.append('name', this.newTask.name);
                formData.append('email', this.newTask.email);
                axios.post('/tasks/store', formData).then(response => {

                });
                this.showNewTask = false;
                this.preview = false;
                this.showAuth = false;
                window.location = "/";
            },
            setStatus(task, status){
                let formData = new FormData();
                formData.append('task_id', task.id);
                formData.append('status', status);
                formData.append('user_email', this.user.email);
                formData.append('user_id', this.user.id);
                axios.post('/tasks/status', formData).then(response => {
                    if (response.data != 'forbidden') {
                        task.status = status;
                    }
                    else alert('forbidden');

                });
            }
        }
    });

    var sortByName = function(d1, d2){return (d1.user_name.toLowerCase() > d2.user_name.toLowerCase()) ? 1 : -1;};
    var sortByEmail = function(d1, d2){return (d1.user_email.toLowerCase() > d2.user_email.toLowerCase()) ? 1 : -1;};
</script>

