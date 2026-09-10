import { memo, useEffect, useState } from "react";
import { Helmet } from "react-helmet-async";
import api from "../api/client";
import { useAuth } from "../context/AuthContext";

// React.memo evita que cada item da lista seja re-renderizado quando o
// estado de busca muda mas o próprio livro não — só recalcula se as props
// dele mudarem de fato.
const LivroItem = memo(function LivroItem({ livro, podeEmprestar, onEmprestar }) {
  return (
    <li>
      {livro.capa_url && (
        <img
          src={livro.capa_url}
          alt={`Capa de ${livro.titulo}`}
          width={40}
          height={60}
          loading="lazy" // adia o carregamento de imagens fora da viewport
        />
      )}
      <div>
        <strong>{livro.titulo}</strong>
        <span> — {livro.autor?.nome}</span>
      </div>
      {podeEmprestar && <button onClick={() => onEmprestar(livro.id)}>Emprestar</button>}
    </li>
  );
});

export default function Livros() {
  const [livros, setLivros] = useState([]);
  const [busca, setBusca] = useState("");
  const [carregando, setCarregando] = useState(true);
  const { usuario } = useAuth();

  useEffect(() => {
    buscarLivros();
  }, [busca]);

  function buscarLivros() {
    setCarregando(true);
    api
      .get("/livros", { params: { busca } })
      .then((res) => setLivros(res.data.data))
      .finally(() => setCarregando(false));
  }

  async function emprestar(livroId) {
    try {
      await api.post(`/livros/${livroId}/emprestimos`);
      buscarLivros();
    } catch (err) {
      alert(err.response?.data?.message ?? "Não foi possível emprestar este livro.");
    }
  }

  return (
    <div className="pagina-livros">
      <Helmet>
        <title>Acervo — Biblioteca</title>
        <meta name="description" content="Consulte o acervo completo da Biblioteca e empreste livros disponíveis." />
      </Helmet>
      <h1>Acervo</h1>
      <input
        type="search"
        placeholder="Buscar por título..."
        value={busca}
        onChange={(e) => setBusca(e.target.value)}
      />

      {carregando ? (
        <p>Carregando...</p>
      ) : (
        <ul className="lista-livros">
          {livros.map((livro) => (
            <LivroItem
              key={livro.id}
              livro={livro}
              podeEmprestar={!!usuario}
              onEmprestar={emprestar}
            />
          ))}
        </ul>
      )}
    </div>
  );
}
