import { lazy, Suspense } from "react";
import { Navigate, Route, Routes, Link } from "react-router-dom";
import { AuthProvider, useAuth } from "./context/AuthContext";
import "./App.css";

// code splitting: cada página vira um chunk JS separado, baixado só quando
// a rota é acessada — reduz o bundle inicial que o usuário precisa carregar
const Login = lazy(() => import("./pages/Login"));
const Livros = lazy(() => import("./pages/Livros"));
const CadastroLivro = lazy(() => import("./pages/CadastroLivro"));
const Emprestimos = lazy(() => import("./pages/Emprestimos"));

function RotaProtegida({ children }) {
  const { usuario, carregando } = useAuth();
  if (carregando) return <p>Carregando...</p>;
  return usuario ? children : <Navigate to="/login" replace />;
}

function Cabecalho() {
  const { usuario, logout } = useAuth();
  return (
    <header className="cabecalho">
      <Link to="/livros">📚 Biblioteca</Link>
      <nav>
        <Link to="/livros">Acervo</Link>
        {usuario && <Link to="/livros/novo">Cadastrar livro</Link>}
        {usuario && <Link to="/emprestimos">Meus empréstimos</Link>}
        {usuario ? (
          <button onClick={logout}>Sair ({usuario.nome})</button>
        ) : (
          <Link to="/login">Entrar</Link>
        )}
      </nav>
    </header>
  );
}

export default function App() {
  return (
    <AuthProvider>
      <Cabecalho />
      <main>
        <Suspense fallback={<p>Carregando página...</p>}>
          <Routes>
            <Route path="/" element={<Navigate to="/livros" replace />} />
            <Route path="/login" element={<Login />} />
            <Route path="/livros" element={<Livros />} />
            <Route
              path="/livros/novo"
              element={
                <RotaProtegida>
                  <CadastroLivro />
                </RotaProtegida>
              }
            />
            <Route
              path="/emprestimos"
              element={
                <RotaProtegida>
                  <Emprestimos />
                </RotaProtegida>
              }
            />
          </Routes>
        </Suspense>
      </main>
    </AuthProvider>
  );
}
