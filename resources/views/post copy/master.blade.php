@extends('welcome')


@section('content')
<div class="row">

        <div class="col-md-8 mx-auto mt-5">
            <a href="{{route('post.excel')}}" class="btn btn-info">Csv</a>
            <button id="printDivBtn">printDivBtn</button>
                <ul class="list-group list-group-flush" >
                        <li class="list-group-item">
                            <input type="text" class="form-control" id="searchPost" placeholder="Search Post..">
                        </li>
                </ul>        
            <div id="postList">

            </div>
            <div id="modals">

            </div>
        </div>
        <div class="col-md-8 mx-auto mt-5">
                <form id="createForm" acceptCharset = "UTF-8">
                    @csrf
                    <div class="form-group">
                          <label for="exampleInputEmail1">Name</label>
                          <input type="text" class="form-control" name="name"  placeholder="Enter Name">
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Title</label>
                          <input type="text" class="form-control" name="title" placeholder="Title">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit</button>
                </form>
        </div>
      </div>
@endsection

@push('script')
<script>
       
        // action= method=
            $(document).ready(function(){

                $(document).on("submit","#createForm",function(e){
                    e.preventDefault();
                    let frmData = $(this).serialize();
                    $.ajax({
                        url:"{{route('post.create')}}",
                        type:"POST",
                        data : frmData,
                    })
                    .done(function(data){
                         getData();
                        // console.log(data);
                        // $("#postList .list-group").prepend(`
                        // <li class="list-group-item">
                        //     ${data.name}
                        //     <span class="float-right">
                        //         <button class="btn btn-info postEdit" data-id="${data.id}">*</button>
                        //     </span> 
                        // </li>    
                        // `);
                    })
                    .fail(function(err){
                        console.log(err);
                    });
                })

                function getData(){
                    $.get("{{route('posts')}}",function(data){
                        // console.log(data);
                    $("#postList").empty().append(data);
              
                    })
                }
                $(function(){
                    getData();
                })

                var doc = new jsPDF();

                $('#printDivBtn').click(function () {   
                    doc.fromHTML($('#postList .list-group').html(), 15, 15, {
                        'width': 170,
                            // 'elementHandlers': specialElementHandlers
                    });
                    doc.save('sample-file.pdf');
                });

              
                // add post
                $("#postList").on("click","#addPost",function(e){
                    e.preventDefault();
                    // alert("hello");modals
                    $.get("{{route('post.create')}}",function(data){
                        $("#modals").empty().append(data);
                        $("#modals #postModal").modal("show");
                    })
                })

                $("#postList").on("click",".pagination a",function(e){
                    e.preventDefault();
                    let url = e.target.href;
             

              
                    $.get(url,function(data){
                        console.log(data);
                        $("#postList").empty().append(data);
                    })
                });

                $("#searchPost").on("keyup",function(e){
                    e.preventDefault();
                    let route = "{{route('posts')}}";
                    let param = $(this).val();
                    let url = route + '/' + param;
                    // console.log(route);
                    // console.log(param);
                    // console.log(url);
                    
                    $.get(url,function(data){
                        // console.log(data);
                        $("#postList").empty().append(data);
                    })
                });

                $("#postList").on("click",".postEdit",function(e){
                    alert($(this).data("id"));
                });

                

            })//end of document ready
        
        </script>
  
@endpush