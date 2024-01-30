<div class="container-fluid">
    <div class="row">
    <div class="col-md-12 col-sm-12 col-lg-12">
        <div class="card px-5 py-5">
            <div class="row justify-content-between ">
                <div class="align-items-center col">
                    <h6>URL</h6>
                </div>
                <div class="align-items-center col">
                    <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 btn-sm bg-gradient-primary">Create URL Short</button>
                </div>
            </div>
            <hr class="bg-secondary"/>
            <div class="table-responsive">
            <table class="table  table-flush" id="tableData">
                <thead>
                <tr class="bg-light">
                    <th>No</th>
                    <th>Original URL</th>
                    <th>Short URL</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="tableList">

                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
</div>

<script>

    getURLList();

    async function getURLList(){
        let tableData = $("#tableData");
        let tableList = $("#tableList");

        tableData.DataTable().destroy();
        tableList.empty();

        showLoader();
        let response = await axios.get('short-url-list');
        hideLoader();

       response.data['data'].forEach(function(item, index){
        let row = `<tr>
                    <td>${index+1}</td>
                    <td>${item['original_url']}</td>
                    <td>${item['short_url']}</td>
                    <td>
                        <button data-id="${item['id']}" class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                    </td>
                 </tr>`
                 tableList.append(row);
       });


       $(".deleteBtn").on('click', function(){
            let id = $(this).data("id");
            $("#delete-modal").modal("show");
            $("#deleteID").val(id);
       });


       new DataTable('#tableData',{
            order:[[0,'desc']],
            lengthMenu:[5,10,15,20,30]
        });


    }

</script>