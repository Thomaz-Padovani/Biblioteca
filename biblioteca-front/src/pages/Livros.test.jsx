import { render, screen, waitFor } from "@testing-library/react";
import { BrowserRouter } from "react-router-dom";
import { describe, expect, it, vi } from "vitest";
import { AuthProvider } from "../context/AuthContext";
import api from "../api/client";
import Livros from "./Livros";

// mocka o client HTTP para não depender do back-end real durante o teste
vi.mock("../api/client");

describe("Livros", () => {
  it("lista os livros retornados pela API", async () => {
    api.get.mockResolvedValueOnce({
      data: {
        data: [
          { id: 1, titulo: "Clean Code", autor: { nome: "Robert C. Martin" } },
          { id: 2, titulo: "Refactoring", autor: { nome: "Martin Fowler" } },
        ],
      },
    });

    render(
      <BrowserRouter>
        <AuthProvider>
          <Livros />
        </AuthProvider>
      </BrowserRouter>,
    );

    expect(screen.getByText(/carregando/i)).toBeInTheDocument();

    await waitFor(() => {
      expect(screen.getByText("Clean Code")).toBeInTheDocument();
      expect(screen.getByText(/Refactoring/)).toBeInTheDocument();
    });
  });
});
