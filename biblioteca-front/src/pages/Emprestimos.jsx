import { useEffect, useState } from "react";
import api from "../api/client";

export default function Emprestimos() {
  const [emprestimos, setEmprestimos] = useState([]);

  useEffect(() => {
    carregar();
  }, []);

  function carregar() {
    api.get("/emprestimos", { params: { em_aberto: true } }).then((res) => setEmprestimos(res.data.data));
  }

  async function devolver(id) {
    await api.patch(`/emprestimos/${id}/devolucao`);
    carregar();
  }

  return (
    <div className="pagina-emprestimos">
      <h1>Meus empréstimos em aberto</h1>
      <ul>
        {emprestimos.map((emp) => (
          <li key={emp.id}>
            <span>{emp.livro?.titulo}</span>
            <span> — devolução prevista: {emp.data_devolucao_prevista}</span>
            <button onClick={() => devolver(emp.id)}>Registrar devolução</button>
          </li>
        ))}
      </ul>
    </div>
  );
}
