<div class="container">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card animated fadeIn w-100 p-3">
                <div class="card-body">
                    <h4>My Profile</h4>
                    <hr/>
                    <div class="container-fluid m-0 p-0">
                        <div class="row m-0 p-0">
                            <div class="col-md-6 p-2">
                                <label>Email Address</label>
                                <input readonly id="email" placeholder="User Email" class="form-control" type="email" value=""/>
                            </div>
                            <div class="col-md-6 p-2">
                                <label>Full Name</label>
                                <input id="fullName" placeholder="Full Name" class="form-control" type="text"/>
                            </div>
                            <div class="col-md-6 p-2">
                                <label>Mobile Number</label>
                                <input id="phone" placeholder="Mobile" class="form-control" type="mobile"/>
                            </div>
                            <div class="col-md-6 p-2">
                                <label>Password</label>
                                <input id="password" placeholder="User Password" class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="row m-0 p-0">
                            <div class="col-md-4 p-2">
                                <button onclick="onUpdate()" class="btn mt-3 w-100  btn-primary">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>

    getUserProfileInfo();

    async function getUserProfileInfo(){
        showLoader();
        let response = await axios.get("/user-profile");
        hideLoader();

        document.getElementById("email").value = response.data['data']['email'];
        document.getElementById("fullName").value = response.data['data']['name'];
        document.getElementById("phone").value = response.data['data']['phone'];
        document.getElementById("password").value = response.data['data']['password'];
    }

    async function onUpdate(){
        let email = document.getElementById("email").value;
        let fullName = document.getElementById("fullName").value;
        let phone = document.getElementById("phone").value;
        let password = document.getElementById("password").value;

        if(fullName.length ==0){
            errorToast("Name is required");
        }else if(phone.length ==0){
            errorToast("Phone is required");
        }else if(password.length ==0){
            errorToast("Password is required");
        }else{
            showLoader();
            let response = await axios.post("/user-profile-update", {
                "name":fullName,
                "phone":phone,
                "password":password
            });
            hideLoader();

            if(response.status === 200 && response.data['status'] === 'success'){
                successToast(response.data['message']);
                await getUserProfileInfo();
            }else{
                errorToast(response.data['message']);
            }
        }
    }

</script>