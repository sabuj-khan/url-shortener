<div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">URL Shortener</h6>
                </div>
                <div class="modal-body">
                    <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label">Put URL *</label>
                                <input type="text" class="form-control" id="urlName">
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn btn-sm btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="SaveURL()" id="save-btn" class="btn btn-sm  btn-success" >Save</button>
                </div>
            </div>
    </div>
</div>

<script>

    async function SaveURL(){
        let url = document.getElementById("urlName").value;

        if(url.length == 0){
            errorToast("URL is required");
        }else{
            document.getElementById("modal-close").click();

            showLoader();
            let response = await axios.post('/create-short-url', {"original_url":url});
            hideLoader();

            if(response.status === 201 && response.data['status'] === 'success'){
                successToast(response.data['message']);
                document.getElementById("save-form").reset();

                await getURLList();
            }else{
                errorToast("Request fail to create url short");
            }
        }
    }

</script>
