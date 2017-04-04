<html>
<head>
    <title>Register User</title>
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
      <form method="post">
          <div class="container">
              <div class="row">
                  <div class="panel-heading"><h2>Student Details</h2></div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                    <label for="studentName">Student Name</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="studentName" id="studentName" value="{{ Input::old('studentName') }}" aria-required="true"/>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                    <label for="dob" >Date of Birth</label>
                </div>
                <div class="col-sm-6">
                    <input type="date" name="dob" id="dob" value="{{ Input::old('dob') }}" aria-required="true"/>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                    <label for="studentGender">Gender</label>
                </div>
                <div class="col-sm-6" aria-required="true">
                    <input type="radio" name="studentGender"value="F"/>Female</br>
                    <input type="radio" name="studentGender"value="M"/>Male</br>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-6">
                    <label for="gradeId">Grade</label>
                </div>
                <div class="col-sm-6" aria-required="true">
                    <input type="radio" name="gradeId"value="1"/>Playgroup</br>
                    <input type="radio" name="gradeId"value="2"/>Nursery</br>
                    <input type="radio" name="gradeId"value="3"/>J.K.G.</br>
                    <input type="radio" name="gradeId"value="4"/>S.K.G.</br>
                </div>
            </div>
          </div>
          <div class="container">
            <div class="row">
                <div class="panel-heading"><h2>Parent Details</h2></div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="parentName">Parent Name</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="parentName" id="parentName" value="{{ Input::old('parentName') }}" aria-required="true"/>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="parentGender">Gender</label>
                </div>
                <div class="col-sm-6" aria-required="true">
                    <input type="radio" name="parentGender"value="F"/>Female</br>
                    <input type="radio" name="parentGender"value="M"/>Male</br>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="address">Address</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="address" id="address" value="{{ Input::old('address') }}" aria-required="true"/>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="contact">Contact</label>
                </div>
                <div class="col-sm-6">
                    <input type="text" name="contact"  id="contact" value="{{ Input::old('contact') }}" aria-required="true"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label for="role">Role</label>
                </div>
                <div class="col-sm-6" aria-required="true">
                    <input type="radio" name="role"value="2"/>Parent</br>
                    <input type="radio" name="role"value="3"/>Principal</br>
                    <input type="radio" name="role"value="4"/>Teacher</br>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="username">Username</label>
                </div>
                <div class="col-sm-6">
                    <input type="email" name="username" id="address" value="{{ Input::old('username') }}" aria-required="true"/>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <label for="password">Password</label>
                </div>
                <div class="col-sm-6">
                    <input type="password" name="password" id="password" aria-required="true" />
                </div>
            </div>
          </div>

          <div class="container">
            <div class="row">
                <div class="col-sm-12" >
                    <button type="button" class="btn btn-success pull-right">Register User</button>
                </div>
            </div>
          </div>

      </form>
</body>
</html>