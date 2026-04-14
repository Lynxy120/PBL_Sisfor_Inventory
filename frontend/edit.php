<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Personal Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light p-5">

    <!-- Button trigger modal -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        Edit Personal Information
    </button>

    <!-- ===== MODAL ===== -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Personal Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <form id="editForm">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" id="fname" class="form-control" value="Musharof">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" id="lname" class="form-control" value="Chowdhury">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" id="email" class="form-control" value="randomuser@pimjo.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" id="phone" class="form-control" value="+09 363 398 46">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bio</label>
                            <input type="text" id="bio" class="form-control" value="Team Manager">
                        </div>

                    </form>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">
                        Save Changes
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
</body>

</html>