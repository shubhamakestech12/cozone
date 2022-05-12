<div class="contents" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Enter Price Plans</h4>
                    <div class="breadcrumb-action justify-content-center flex-wrap">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-element">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card card-default card-md mb-4">
                        <div class="card-header">
                            <h6>EnterPrise Plan</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form id="frm_enter_prise">
                                        <input type="hidden" name="_token" value="">       
                                               <input type="hidden" name="id" id="id">
                                        <div class="form-row mx-n15">
                                            <div class="col-md-12 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Plan Name</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Plan Name" name="plan_name" id="plan_name">
                                            </div>
                                            <div class="col-md-12 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Description</label>
                                                <textarea type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Plan Description" name="description" id="description"></textarea>
                                            </div>
                                                        
                                        </div> 
                                        <button class="btn btn-xs btn-primary px-30 float-right mt-3" type="submit" id="button">Save</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ends: .card -->
                </div>
                <div class="col-8 ">
                <div class="card mb-25">
                    <div class="card-header">
                        <h6>Enterprise Plan List</h6>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="drag-drop-wrap">
                            <div class="table-responsive table-revenue w-100 mb-30">
                                <table class="table mb-0 table-basic" id="enter_prise_datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Plan Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ends: card -->
            </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

