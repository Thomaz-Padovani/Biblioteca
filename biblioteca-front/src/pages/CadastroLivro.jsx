import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "../api/client";

export default function CadastroLivro() {
  const [autores, setAutores] = useState([]);
  const [form, setForm] = useState({ titulo: "", isbn: "", ano_publicacao: "", autor_id: "" });
  const [capa, setCapa] = useState(null);
  const [erro, setErro] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    api.get("/autores").then((res) => setAutores(res.data.data));
  }, []);

  function handleChange(e) {
    setForm({ ...form, [e.target.name]: e.target.value });
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setErro(null);
    try {
      // 1) cria o livro (dados em JSON)
      const { data: livro } = await api.post("/livros", form);

      // 2) se uma capa foi selecionada, envia em uma segunda requisição multipart
      if (capa) {
        const dadosCapa = new FormData();
        dadosCapa.append("capa", capa);
        await api.post(`/livros/${livro.id}/capa`, dadosCapa, {
          headers: { "Content-Type": "multipart/form-data" },
        });
      }

      navigate("/livros");
    } catch (err) {
      setErro(err.response?.data?.message ?? "Não foi possível cadastrar o livro.");
    }
  }

  return (
    <div className="pagina-cadastro-livro">
      <h1>Cadastrar novo livro</h1>
      <form onSubmit={handleSubmit}>
        <label>
          Título
          <input name="titulo" value={form.titulo} onChange={handleChange} required />
        </label>
        <label>
          ISBN
          <input name="isbn" value={form.isbn} onChange={handleChange} required />
        </label>
        <label>
          Ano de publicação
          <input name="ano_publicacao" type="number" value={form.ano_publicacao} onChange={handleChange} />
        </label>
        <label>
          Autor
          <select name="autor_id" value={form.autor_id} onChange={handleChange} required>
            <option value="">Selecione...</option>
            {autores.map((autor) => (
              <option key={autor.id} value={autor.id}>{autor.nome}</option>
            ))}
          </select>
        </label>
        <label>
          Capa do livro (opcional)
          <input
            type="file"
            accept="image/png, image/jpeg, image/webp"
            onChange={(e) => setCapa(e.target.files[0])}
          />
        </label>
        {erro && <p className="erro">{erro}</p>}
        <button type="submit">Cadastrar</button>
      </form>
    </div>
  );
}
