import { render, screen } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { describe, expect, it } from "vitest";
import { AuthProvider } from "../context/AuthContext";
import Login from "./Login";

function renderLogin() {
  return render(
    <BrowserRouter>
      <AuthProvider>
        <Login />
      </AuthProvider>
    </BrowserRouter>,
  );
}

describe("Login", () => {
  it("renderiza os campos de e-mail e senha", () => {
    renderLogin();

    expect(screen.getByLabelText(/e-mail/i)).toBeInTheDocument();
    expect(screen.getByLabelText(/senha/i)).toBeInTheDocument();
    expect(screen.getByRole("button", { name: /entrar/i })).toBeInTheDocument();
  });

  it("exige e-mail e senha antes de permitir o envio", () => {
    renderLogin();

    const campoEmail = screen.getByLabelText(/e-mail/i);
    const campoSenha = screen.getByLabelText(/senha/i);

    expect(campoEmail).toBeRequired();
    expect(campoSenha).toBeRequired();
  });
});
