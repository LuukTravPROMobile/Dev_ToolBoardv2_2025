import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useAuth } from "../contexts/AuthContext";

function Register() {
    const navigate = useNavigate();
    const { register, isAuthenticated } = useAuth();

    const [formData, setFormData] = useState({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    // Redirect if already authenticated
    if (isAuthenticated) {
        navigate("/dashboard");
    }

    const handleChange = (e) => {
        setFormData({
            ...formData,
            [e.target.name]: e.target.value,
        });
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError("");

        // Validate passwords match
        if (formData.password !== formData.password_confirmation) {
            setError("Passwords do not match");
            return;
        }

        setLoading(true);

        const result = await register(formData);

        if (result.success) {
            navigate("/dashboard");
        } else {
            setError(result.error);
        }

        setLoading(false);
    };

    return (
        <div style={styles.container}>
            <div style={styles.card}>
                <h2 style={styles.title}>Register</h2>

                {error && <div style={styles.error}>{error}</div>}

                <form onSubmit={handleSubmit} style={styles.form}>
                    <div style={styles.formGroup}>
                        <label htmlFor="name" style={styles.label}>
                            Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value={formData.name}
                            onChange={handleChange}
                            required
                            style={styles.input}
                            placeholder="John Doe"
                        />
                    </div>

                    <div style={styles.formGroup}>
                        <label htmlFor="email" style={styles.label}>
                            Email
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value={formData.email}
                            onChange={handleChange}
                            required
                            style={styles.input}
                            placeholder="your@email.com"
                        />
                    </div>

                    <div style={styles.formGroup}>
                        <label htmlFor="password" style={styles.label}>
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            value={formData.password}
                            onChange={handleChange}
                            required
                            minLength={8}
                            style={styles.input}
                            placeholder="Minimum 8 characters"
                        />
                    </div>

                    <div style={styles.formGroup}>
                        <label
                            htmlFor="password_confirmation"
                            style={styles.label}
                        >
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            value={formData.password_confirmation}
                            onChange={handleChange}
                            required
                            minLength={8}
                            style={styles.input}
                            placeholder="Repeat password"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        style={{
                            ...styles.button,
                            ...(loading ? styles.buttonDisabled : {}),
                        }}
                    >
                        {loading ? "Registering..." : "Register"}
                    </button>
                </form>

                <p style={styles.footer}>
                    Already have an account?{" "}
                    <Link to="/login" style={styles.link}>
                        Login here
                    </Link>
                </p>
            </div>
        </div>
    );
}

const styles = {
    container: {
        minHeight: "100vh",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        background: "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
        padding: "20px",
    },
    card: {
        background: "white",
        padding: "40px",
        borderRadius: "12px",
        boxShadow: "0 10px 30px rgba(0,0,0,0.2)",
        width: "100%",
        maxWidth: "400px",
    },
    title: {
        fontSize: "2em",
        marginBottom: "30px",
        textAlign: "center",
        color: "#333",
    },
    error: {
        background: "#fee",
        color: "#c33",
        padding: "12px",
        borderRadius: "8px",
        marginBottom: "20px",
        fontSize: "0.9em",
    },
    form: {
        display: "flex",
        flexDirection: "column",
        gap: "20px",
    },
    formGroup: {
        display: "flex",
        flexDirection: "column",
    },
    label: {
        marginBottom: "8px",
        fontWeight: "600",
        color: "#333",
    },
    input: {
        padding: "12px",
        border: "1px solid #ddd",
        borderRadius: "8px",
        fontSize: "1em",
        outline: "none",
    },
};
