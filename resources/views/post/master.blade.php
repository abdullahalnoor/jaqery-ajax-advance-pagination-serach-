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
                @include('post.index')


                <div id="pagination" class="mt-5">
            
                </div>
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
                      
                    })
                    .fail(function(err){
                        console.log(err);
                    });
                })

               function populateData(data){
                    let output = null;
         
                   data.data.forEach(function(item){
                  
                       output += `<li class="list-group-item ">
                               <span class="float-right">
                                   ${item.name}
                                   <button class="btn btn-info postEdit" data-id="">*</button>
                               </span> 
                           </li>  
                           
                           
                           `; 
                  })
                   $("#postList .list-group").empty().append(output);
                   let cDisabled = data.prev_page_url == null ? 'disabled' : false;
                   let lDisabled = data.next_page_url == null ? 'disabled' : false;
                   var pagiantion = `
                   <nav aria-label="Page navigation example">
                       <ul class="pagination">
                           <li class="page-item ${cDisabled}"><a class="page-link" href="${data.first_page_url}">First</a></li>
                           <li class="page-item ${cDisabled}"><a class="page-link" href="${data.prev_page_url}">Previous</a></li>
                         ${showEveryPage(data)}
                           <li class="page-item ${lDisabled}"><a class="page-link" href="${data.next_page_url}">Next</a></li>
                           <li class="page-item ${lDisabled}"><a class="page-link" href="${data.last_page_url}">Last</a></li>
                       </ul>
                       </nav>
                   `;
                   $("#postList #pagination").empty().append(pagiantion);
                }
                function showEveryPage(data){
                    let cDisabled = data.prev_page_url == null ? 'disabled' : false;
                   let lDisabled = data.next_page_url == null ? 'disabled' : false;
                   
               
                    let current_page = ( data.current_page -3 )  > 0 ?  (data.current_page -3)  : 1 ;
                    let last = Math.ceil(data.total /data.per_page) ;
                    let last_page = ((data.current_page +3)  < last)  ?  (data.current_page +3 ) : last ;

                   
                    let pages ;
                  
                    let start = ( data.current_page - 3)  > 0 ?  (data.current_page -3)  : 1 ;

                    
                    if(   current_page > 1   ){
                        pages +=    `
                    <li class="page-item ${cDisabled}"><a class="page-link" href="${data.first_page_url}">1</a></li>
                    <li class="page-item ${cDisabled}"><a class="page-link" href="">...</a></li>
                        
                        `;
                    }    
                    
                    for(i=current_page ; i <= last_page  ; i++){
                        var active = data.current_page == i ? 'active' : false;
                       
                         pages += ` 
                            <li class="page-item ${active}"><a class="page-link" href="${data.path}?page=${i}">${i}</a></li>
                            `;
                    }

                    if(last_page < last){
                        pages +=    `
                    <li class="page-item ${lDisabled}"><a class="page-link" href="">...</a></li>
                    <li class="page-item ${lDisabled}"><a class="page-link" href="${data.last_page_url}">${last }</a></li>
                        
                        `;
                    } 

                    return pages;
                   
                }

                function getData(data = null){
                   if(data){
                    populateData(data);

                   }else{
                    $.get("{{route('posts')}}",function(data){
                       
                    populateData(data);
                   
                    })
                   }
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
             
                    console.log(url);
              
                    $.get(url,function(data){
                        console.log(data);
                       getData(data);
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
                        getData(data);
                        // $("#postList").empty().append(data);
                    })
                });

                $("#postList").on("click",".postEdit",function(e){
                    alert($(this).data("id"));
                });

                

            })//end of document ready
        
        </script>
  
@endpush