<x-guest-layout>
    <div class="mb-4 p-4">
        <div class="text-center">
            <h1 class="h1 mb-4 fw-bold">Login</h1>
        </div>

        <!-- Status Session (misalnya setelah reset password sukses) -->
        <x-auth-session-status class="mb-4 text-success" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="user">
            @csrf

            <!-- Email -->
            <div class="form-group text-left mb-4">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control form-control-user @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required autofocus autocomplete="username"
                    placeholder="Masukkan alamat email...">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group text-left">
                <label for="email">Password</label>
                <input type="password" name="password" id="password"
                    class="form-control form-control-user @error('password') is-invalid @enderror" required
                    autocomplete="current-password" placeholder="Masukkan password...">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <hr class="mb-4 mt-4">
            <!-- Submit -->
            <button type="submit" class=" btn btn-primary btn-user btn-block">
                Login
            </button>
        </form>
    </div>
</x-guest-layout>
