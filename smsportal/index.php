<?php
include_once 'header.php';
?>
    
    <div class="loginPage">
        <div class="container">
            <div class="loginContainer">
                <div class="loginBody">
                    <h1>SMS Portal Login</h1>
                    <form class="loginForm">
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="User ID" name="userid" id="username" maxlength="30">
                             <span class="alertMsg userAlert" style="display: none;">Username is required</span>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" placeholder="Password" name="password" id="password" maxlength="30">
                            <span class="alertMsg passwordAlert" style="display: none;">Password is required</span>
                        </div>
                        <button class="btn btn-lightgreen" type="submit"  onClick="login();">Login</button>
                        <div class="alert alert-danger alert-dismissable" style="display: none;">
                             <a class="close" data-dismiss="alert" aria-label="close" >&times;</a>User ID or Password is incorrect
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
include_once 'footer.php';
?>