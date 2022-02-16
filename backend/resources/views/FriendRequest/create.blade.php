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
    <form action="{{ route('friend.request.store') }}" method="POST">
        @csrf
        <label for="destination_id">ユーザーID</label>
        <input type="number" id="destination_id" name="destination_id">

        <label for="message">メッセージ</label>
        <input type="text" id="message" name="message">

        <button>送信</button>
    </form>
</div>
