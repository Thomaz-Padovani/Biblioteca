import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { useAuth } from "../context/AuthContext";

export default function Login() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [erro, setErro] = useState(null);
  const { login } = useAuth();
  const navigate = useNavigate();

  async function handleSubmit(e) {
    e.preventDefault();
    setErro(null);
    try {
      await login(email, password);
      navigate("/livros");
    } catch (err) {
      setErro("E-mail ou senha inválidos.");
    }
  }

  return (
    <div className="pagina-login">
      <Helmet>
        <title>Entrar — Biblioteca</title>
        <meta name="description" content="Acesse sua conta para emprestar livros e acompanhar suas reservas na Biblioteca." />
      </Helmet>
      <h1>Entrar na Biblioteca</h1>
      <form onSubmit={handleSubmit}>
        <label>
          E-mail
          <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
        </label>
        <label>
          Senha
          <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} required />
        </label>
        {erro && <p className="erro">{erro}</p>}
        <button type="submit">Entrar</button>
      </form>
    </div>
  );
}
