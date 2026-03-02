# 🎓 AcaHub — Education Connect Simply

**Opsi 1 — Python HTTP Server (Recommended):**
```bash
# Buka terminal di folder project
cd acahubkelompok

# Jalankan server
python -m http.server 8080

# Buka di browser
# http://localhost:8080
```

**Opsi 2 — Buka langsung:**
```
Klik dua kali file index.html di browser
```

**Opsi 3 — VS Code Live Server:**
1. Install extension **Live Server** di VS Code
2. Klik kanan pada `index.html` → *Open with Live Server*

---

## 🎨 Design System

Project ini menggunakan design system yang konsisten. Berikut referensi utama:

### Warna Brand
| Variable | Warna | Hex |
|---|---|---|
| `--primary` | Teal | `#0d7377` |
| `--primary-dark` | Teal Gelap | `#095c5f` |
| `--primary-light` | Teal Terang | `#14919b` |
| `--accent` | Orange | `#e87a2e` |
| `--accent-dark` | Orange Gelap | `#d06820` |
| `--accent-light` | Orange Terang | `#f59e4f` |

### Font
- **Heading:** `Outfit` (Google Fonts) — weight: 600–900
- **Body:** `Inter` (Google Fonts) — weight: 400–600

### CSS Variables
Semua variabel design ada di `:root` dalam file `styles.css` (baris 12–68), termasuk:
- Warna brand & semantic
- Shadows (`--shadow-sm` sampai `--shadow-xl`)
- Border radius (`--radius-sm` sampai `--radius-full`)
- Typography & spacing

---

