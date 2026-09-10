import { createContext, useContext, useEffect, useState } from "react";
import api from "../api/client";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [usuario, setUsuario] = useState(null);
  const [carregando, setCarregando] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem("token");
    if (!token) {
      setCarregando(false);
      return;
    }
    api
      .get("/me")
      .then((res) => setUsuario(res.data))
      .catch(() => localStorage.removeItem("token"))
      .finally(() => setCarregando(false));
  }, []);

  async function login(email, password) {
    const { data } = await api.post("/login", { email, password });
    localStorage.setItem("token", data.token);
    setUsuario(data.usuario);
  }

  async function logout() {
    await api.post("/logout");
    localStorage.removeItem("token");
    setUsuario(null);
  }

  return (
    <AuthContext.Provider value={{ usuario, carregando, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
