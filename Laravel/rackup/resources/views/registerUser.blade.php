
    <div class="row">
        <div class="twelve columns">
            <h5>Create User</h5>
        </div>
    </div>
    <div class="row">
        <form method="post">
            <div class="row">
                <div class="two columns">
                    <label for="studentName">Student Name </label>
                </div>
                <div class="ten columns">
                    <input type="text" name="studentName" id="studentName" class="five" value="{{ Input::old('studentName') }}"/>
                </div>

                <div class="two columns">
                    <label for="age" >Age</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="age" id="age" class="five" value="{{ Input::old('age') }}"/>
                </div>

                <div class="two columns">
                    <label for="gradeId">Grade</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="gradeId" id="gradeId" class="five" value="{{ Input::old('gradeId') }}"/>
                </div>

                <div class="two columns">
                    <label for="parentName">Parent Name</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="parentName" id="parentName" class="five" value="{{ Input::old('parentName') }}"/>
                </div>

                <div class="two columns">
                    <label for="gender">Gender</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="gender" id="gender" class="five" value="{{ Input::old('gender') }}"/>
                </div>

                <div class="two columns">
                    <label for="address">Address</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="address" id="address" class="five" value="{{ Input::old('address') }}"/>
                </div>

                <div class="two columns">
                    <label for="role">Role</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="role" id="role" class="five" value="{{ Input::old('role') }}"/>
                </div>

                <div class="two columns">
                    <label for="username">Username</label>
                </div>
                <div class="ten columns">
                    <input type="text" name="username" id="address" class="five" value="{{ Input::old('username') }}"/>
                </div>

                <div class="two columns">
                    <label for="password">Password</label>
                </div>
                <div class="ten columns">
                    <input type="password" name="password" id="password" class="five" />
                </div>

            </div>

            <div class="row">
                <div class="four columns">

                </div>
                <div class="eight columns">
                    <button class="success medium button" href="#">Create User</button>
                </div>
            </div>
        </form>

    </div>
