@extends('admin.layouts.base')

@section('content')
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <a class="btn btn-primary" href="{{route('admin.posts.create')}}">Crea un nuovo post</a>
                <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titolo</th>
                        <th scope="col">Contenuto</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Categoria</th>
                        <th scope="col">Azioni</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($posts as $post)
                          <tr>
                            <td>{{$post->id}}</td>
                            <td>{{$post->title}}</td>
                            <td>{{substr($post->content, 0, 30)}}</td>
                            <td>{{$post->slug}}</td>
                            <td>
                                {{isset($post->category_id) ? $post->category->name : 'N.D.'}}
                              </td>
                            <td class="d-flex justify-content-center">
                              <a href="{{ route('admin.posts.show', $post->id) }}" class="btn btn-primary">Vedi</a>
                              <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-secondary mx-2">Modifica</a>
                              <form method="POST" action="{{route('admin.posts.destroy', $post->id)}}">
                                  @csrf
                                  @method('DELETE')

                                  <button type="submit" class="btn btn-danger">Elimina</button>
                              </form>
                         </td>
                          </tr>
                      @endforeach
                    </tbody>
                  </table>
            </div>
        </div>
    </div>

@endsection