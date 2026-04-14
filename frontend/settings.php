<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f7fb
        }

        .card-box {
            border: 1px solid #e5e7eb;
            border-radius: 12px
        }

        .label {
            font-size: 12px;
            color: #6b7280
        }

        .value {
            font-weight: 500
        }

        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            object-fit: cover
        }

        .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            background: #fff
        }
    </style>
</head>

<body>

    <div class="container my-5">
        <h4 class="mb-3">Profile</h4>

        <!-- ===== HEADER PROFILE ===== -->
        <div class="card card-box p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://i.pravatar.cc/150?img=12" class="avatar" alt="">
                    <div>
                        <div class="fw-semibold">Musharof Chowdhury</div>
                        <div class="text-muted small">Team Manager • Arizona, United States</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="icon-btn">f</button>
                    <button class="icon-btn">x</button>
                    <button class="icon-btn">in</button>
                    <button class="icon-btn">ig</button>
                    <button class="btn btn-outline-primary btn-sm ms-2" onclick="toggleEdit('personal')">Edit</button>
                </div>
            </div>
        </div>

        <!-- ===== PERSONAL INFORMATION ===== -->
        <div class="card card-box p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Personal Information</h6>
                <button class="btn btn-outline-primary btn-sm" onclick="toggleEdit('personal')">Edit</button>
            </div>

            <!-- VIEW -->
            <div id="view-personal">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="label">First Name</div>
                        <div class="value" id="v_fname">Chowdury</div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Last Name</div>
                        <div class="value" id="v_lname">Musharof</div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="label">Email Address</div>
                        <div class="value" id="v_email">randomuser@pimjo.com</div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Phone</div>
                        <div class="value" id="v_phone">+09 363 398 46</div>
                    </div>
                </div>
                <div>
                    <div class="label">Bio</div>
                    <div class="value" id="v_bio">Team Manager</div>
                </div>
            </div>

            <!-- EDIT -->
            <form id="edit-personal" class="d-none">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input id="fname" class="form-control" value="Chowdury">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input id="lname" class="form-control" value="Musharof">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input id="email" type="email" class="form-control" value="randomuser@pimjo.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input id="phone" class="form-control" value="+09 363 398 46">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bio</label>
                    <input id="bio" class="form-control" value="Team Manager">
                </div>
                <button type="button" class="btn btn-success btn-sm" onclick="savePersonal()">Save</button>
            </form>
        </div>

        <!-- ===== ADDRESS ===== -->
        <div class="card card-box p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Address</h6>
                <button class="btn btn-outline-primary btn-sm" onclick="toggleEdit('address')">Edit</button>
            </div>

            <!-- VIEW -->
            <div id="view-address">
                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="label">Country</div>
                        <div class="value" id="v_country">United States</div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">City/State</div>
                        <div class="value" id="v_city">Arizona, United States</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="label">Postal Code</div>
                        <div class="value" id="v_postal">ERT 2489</div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">TAX ID</div>
                        <div class="value" id="v_tax">AS4568384</div>
                    </div>
                </div>
            </div>

            <!-- EDIT -->
            <form id="edit-address" class="d-none">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Country</label>
                        <input id="country" class="form-control" value="United States">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City/State</label>
                        <input id="city" class="form-control" value="Arizona, United States">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Postal Code</label>
                        <input id="postal" class="form-control" value="ERT 2489">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">TAX ID</label>
                        <input id="tax" class="form-control" value="AS4568384">
                    </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" onclick="saveAddress()">Save</button>
            </form>
        </div>

    </div>

    <script src="app.js"></script>
</body>
</html>
