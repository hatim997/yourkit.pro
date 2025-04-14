<div class='btn-group'>
    <a href="{{ route('admin.banners.edit', $id) }}" class='btn btn-primary btn-xs me-2'>
        <i class="fa fa-edit"></i>
    </a>

    <form action="{{ route('admin.banners.destroy', $id) }}" method="POST">
        @method('DELETE')
        @csrf
        <button onclick="return confirm('Are you sure you want to delete this?')" type="submit" class='btn btn-danger btn-xs'><i class="fa fa-trash"></i></button>
    </form>
</div>