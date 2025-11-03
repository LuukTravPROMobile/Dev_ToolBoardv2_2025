import React from "react";
import Modal from "react-modal";
import { Link } from "react-router-dom";
import "../../../css/styles.scss"; // keep this as you said it works

if (typeof document !== "undefined") {
  Modal.setAppElement("#root");
}

const GraduatesModal = ({ isOpen, onClose }) => (
  <Modal
    isOpen={isOpen}
    onRequestClose={onClose}
    contentLabel="Sentry Errors Modal"
    className="ReactModal__Content"
    overlayClassName="ReactModal__Overlay"
  >
    <div className="modal-content">
      <h3 className="modal__title">Graduates</h3>
      <p>Details about all of the graduates go here.</p>
      <Link to="/login" className="link-white">Login</Link>
      <button className="btn-close" onClick={onClose}>Close</button>
    </div>
  </Modal>
);

export {GraduatesModal as default};
