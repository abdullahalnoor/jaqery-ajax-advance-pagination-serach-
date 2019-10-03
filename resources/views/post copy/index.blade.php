<div class="card">
    <div class="card-header">
      Post  <button class="btn btn-info float-right" id="addPost">Add</button>
    </div>
    <div class="card-body">
            <h3 class="text-center">Heloo</h3>
        <ul class="list-group list-group-flush">
                  
            @forelse ($posts as $post)
                <li class="list-group-item">
                    {{$post->name}}
                    <span class="float-right">
                        <button class="btn btn-info postEdit" data-id="{{$post->id}}">*</button>
                    </span> 
                </li>      
            @empty
                 <li class="list-group-item">No Data Found..</li>                
            @endforelse
        
           
        </ul>
        {{$posts->links()}}
    </div>
  </div>