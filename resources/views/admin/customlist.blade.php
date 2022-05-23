<div class="contents" id="top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb-main">
                    <h4 class="text-capitalize breadcrumb-title">Manage City</h4>
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
                            <h6>Manage City</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <form id="frm_city">
                                        <input type="hidden" name="_token" value="">       
                                               <input type="hidden" name="id" id="id">
                                        <div class="form-row mx-n15">
                                            <div class="col-md-12 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">City Name</label>
                                                <input type="text" class="form-control ih-medium ip-light radius-xs b-light" placeholder="Enter Name" name="city_name" id="city_name">
                                            </div>
                                            <div class="col-md-12 mb-20 px-15">
                                                <label for="validationDefault01" class="il-gray fs-14 fw-500 align-center">Select Country</label>
                                                <select class="form-control" name="country" id="country">
                                                    <option value="">Select Country</option>
                                                    @if(!@empty($countries) and count($countries))
                                                        @foreach($countries as $country)
                                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>              
                                        </div> 
                                        <div class="form-row mx-n15">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-20 px-15">
                                                <label for="image" class="il-gray fs-14 fw-500 align-center">Upload Image</label>
                                                <a style="width: 100%;" href="javascript:void(0)" class="btn btn-lg btn-outline-lighten btn-upload" onclick="$('#image').click()"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg> Click to Upload</a>
                                                <input type="file" class="form-control  ih-medium ip-light radius-xs b-light" name="image" id="image" style="opacity: 0;position: absolute;" onchange="showSelectedImage(this)">
                                            </div> 
                                            <div class="col-lg-2 mx-3" id="addcode" style="border:1px solid #e3e6ef;">
                                                <embed src="http://127.0.0.1:8000/images/placeholdernew.png" id="setimg" style="width:100%">
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
                        <h6>City List</h6>
                    </div>
                    <div class="card-body pt-0 pb-0">
                        <div class="drag-drop-wrap">
                            <div class="table-responsive table-revenue w-100 mb-30">
                                <table class="table mb-0 table-basic" id="city_datatable" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
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

