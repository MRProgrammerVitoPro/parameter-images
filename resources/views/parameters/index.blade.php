<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Parameters</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container mt-5">
            <h1>Parameters</h1>
            <a href="{{ route('parameters.create') }}" class="btn btn-primary mb-3">Add New Parameter</a>
            <form method="GET" action="{{ url('parameters') }}">
                <div class="form-group">
                    <input type="text" name="id" class="form-control" placeholder="Search by ID">
                </div>
                <div class="form-group">
                    <input type="text" name="title" class="form-control" placeholder="Search by Title">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Icon</th>
                        <th>Icon Gray</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($parameters as $parameter)
                    <tr>
                        <td>{{ $parameter->id }}</td>
                        <td>{{ $parameter->title }}</td>
                        <td>
                            @if($parameter->images && $parameter->images->icon)
                                <img src="{{ asset('storage/' . $parameter->images->icon) }}" alt="Icon" width="50">
                            @endif
                        </td>
                        <td>
                            @if($parameter->images && $parameter->images->icon_gray)
                                <img src="{{ asset('storage/' . $parameter->images->icon_gray) }}" alt="Icon Gray" width="50">
                            @endif
                        </td>
                        <td>
                            <form action="{{ url('parameters/' . $parameter->id . '/upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="icon">Icon:</label>
                                    <input type="file" name="icon" class="form-control-file">
                                </div>
                                <div class="form-group">
                                    <label for="icon_gray">Icon Gray:</label>
                                    <input type="file" name="icon_gray" class="form-control-file">
                                </div>
                                <button type="submit" class="btn btn-success">Upload</button>
                            </form>
                            @if($parameter->images)
                                <form action="{{ url('parameters/' . $parameter->id . '/delete/icon') }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Icon</button>
                                </form>
                                <form action="{{ url('parameters/' . $parameter->id . '/delete/icon_gray') }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Icon Gray</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>