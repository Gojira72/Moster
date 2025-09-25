<style>
/* Header Styles */
.site-header {
  width: 100%;
  background: linear-gradient(to right,#0d0614, #0d0614, #582574, #582574);
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1000;
}

.navbar .container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  font-size: 1.8rem;
  font-weight: bold;
  color: #fff;
  text-decoration: none;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0;
}

.nav-links li a {
  color: #fff;
  text-decoration: none;
  font-size: 1rem;
  transition: color 0.3s;
}

.nav-links li a:hover {
  color: #f39c12;
}

.logout-form {
  display: inline;
}

.logout-btn {
  background: none;
  border: none;
  color: #fff;
  font-size: 1rem;
  cursor: pointer;
  transition: color 0.3s;
}

.logout-btn:hover {
  color: #f39c12;
}

/* Responsividade (opcional) */
@media (max-width: 768px) {
  .navbar .container {
    flex-direction: column;
    align-items: flex-start;
  }
  .nav-links {
    margin-top: 10px;
    flex-direction: column;
    gap: 10px;
  }
}

</style>

<header class="site-header">
  <nav class="navbar">
    <div class="container">
      <a href="{{ route('welcome') }}" class="logo">GHOUL HIGH</a>
      <ul class="nav-links">
        <li><a href="{{ route('welcome') }}">Início</a></li>
        <li><a href="{{ route('feedback.create') }}">Contato</a></li>
        @guest
          <li><a href="{{ route('login') }}">Login</a></li>
          <li><a href="{{ route('register.index') }}">Cadastre-se</a></li>
        @else
          <li><a href="{{ route('teste') }}">Área restrita</a></li>
          <li><a href="#">{{ auth()->user()->nomeUsuario ?? auth()->user()->name ?? auth()->user()->email }}</a></li>
          <li>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
              @csrf
              <button type="submit" class="logout-btn">Logout</button>
            </form>
          </li>
        @endguest
      </ul>
    </div>
  </nav>
</header>