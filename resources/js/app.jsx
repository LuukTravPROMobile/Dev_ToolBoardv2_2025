import "./bootstrap";
import React from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import UsersList from "../../app/components/UsersList";
import Login from "../../app/components/Login";

function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<UsersList />} />
                <Route path="/login" element={<Login />} />
            </Routes>
        </BrowserRouter>
    );
}

const container = document.getElementById("app");
const root = createRoot(container);
root.render(<App />);
root.render(<App />);
