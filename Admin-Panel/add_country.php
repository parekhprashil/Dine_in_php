<?php include("./layouts/sidebar.php"); ?>

<body>
  <div class="pc-container">
    <div class="pc-content">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h5>Add Country</h5>
            </div>
            <div class="card-body">
              <form class="form-horizontal p-4 needs-validation" id="countryForm" enctype="multipart/form-data" novalidate>
                <div class="form-group row">
                  <label for="country_name" class="col-md-2 col-12 text-start text-md-end control-label col-form-label">
                    Country Name
                  </label>
                  <div class="col-md-10 col-12">
                    <input type="text" name="country_name" class="form-control" id="country_name" placeholder="Enter Country Name" required />
                    <span class="text-danger d-block mt-1" id="country_error"></span>
                  </div>
                </div>
                <div class="text-center mt-3">
                  <button type="submit" class="btn btn-primary w-40">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include_once './layouts/footer.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="script.js"></script>

</body>