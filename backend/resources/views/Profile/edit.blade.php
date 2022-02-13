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
    <form action="{{ route('profile.store') }}" method="POST">
        @csrf
        <label for="name">名前を入力してください</label>
        <input type="text" id="name" name="name">

        <label for="description">説明文</label>
        <textarea id="description" name="description"></textarea>

        <label for="location">現在地</label>
        <input type="text" id="location" name="location">

        <button>送信</button>
    </form>
</div>
