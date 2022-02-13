<div>
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('account.store') }}" method="POST">
        @csrf
        <label for="media_id">メディアID</label>
        <input type="number" id="media_id" name="media_id">

        <label for="account_url">アカウントURL</label>
        <input type="text" id="account_url" name="account_url">

        <button>送信</button>
    </form>
</div>
