import React from "react";
import Modal from "react-modal";
import { Link } from "react-router-dom";
import "../../css/styles.scss";

if (typeof document !== "undefined") {
  Modal.setAppElement("#root");
}

const LoginModal = ({ isOpen, onClose }) => (
<Modal
    isOpen={isOpen}
    onRequestClose={onClose}
    contentLabel="Login Modal"
    className="ReactModal__Content"
    overlayClassName="ReactModal__Overlay"
>
    <div className="modal-content">
        <h3 className="modal__title">Login</h3>
        <p>Login to acces TravPRO Mobile's company dashboard</p>
        <Link to="/login">Login</Link>
        <button className="btn-close" onClick={onClose}>Close</button>
    </div>
</Modal>

);

export {LoginModal as default};