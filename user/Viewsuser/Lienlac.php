<?php
// lienlac.php chưa xong phải sửa tiếp
include 'header.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Liên hệ - The Grand Elegance Hotel</title>
  <meta name="description" content="Trang liên hệ khách sạn - địa chỉ, bản đồ, mạng xã hội, mail và form liên hệ." />

  <style>
    :root{
      --primary:#0b7285;
      --accent:#0ea5a4;
      --muted:#6b7280;
      --bg:#f8fafc;
      --card:#ffffff;
      --radius:12px;
      --maxwidth:1100px;
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--bg);color:#0f172a}

    .contact-container{max-width:var(--maxwidth);margin:28px auto;padding:20px}

    /* 📌 Hero có background ảnh — mày thay link ảnh tại đây */
    .contact-hero{
      display:flex;
      gap:20px;
      align-items:center;
      margin-bottom:18px;
      padding:30px;
      border-radius:12px;
      background: url('https://masterisevietnam.com/wp-content/uploads/2021/05/thiet-ke-sanh.jpg.webp') no-repeat center/cover; /* 👉 Thay ảnh ở đây */
      color:white;
    }
    .contact-logo{
      width:64px;height:64px;border-radius:12px;
      background:linear-gradient(135deg,var(--primary),var(--accent));
      display:flex;align-items:center;justify-content:center;
      color:white;font-weight:700
    }
    .contact-hero h1{margin:0;font-size:22px}
    .contact-hero p{margin:0;color:#f1f5f9;font-size:14px}

    .contact-grid{display:grid;grid-template-columns:1fr 420px;gap:18px}
    .contact-card{background:var(--card);border-radius:var(--radius);padding:18px;box-shadow:0 6px 18px rgba(10,20,30,0.06)}

    .contact-about h2{margin-top:0}
    .contact-info{display:flex;flex-direction:column;gap:12px;margin-top:12px}
    .contact-info-row{display:flex;gap:12px;align-items:flex-start}
    .contact-info-icon{width:44px;height:44px;border-radius:10px;background:#eef6f7;display:flex;align-items:center;justify-content:center}
    .contact-info-text{font-size:14px}
    .contact-info-text .label{display:block;color:var(--muted);font-size:12px}
    .contact-socials{display:flex;gap:8px;margin-top:10px}
    .contact-socials a{display:inline-flex;align-items:center;justify-content:center;padding:8px;border-radius:8px;border:1px solid #eef2f7;text-decoration:none}

    .contact-map{height:320px;border-radius:10px;overflow:hidden}
    .contact-map iframe{width:100%;height:100%;border:0}

    form.contact-form{display:flex;flex-direction:column;gap:10px}
    form.contact-form input[type=text],
    form.contact-form input[type=email],
    form.contact-form textarea{width:100%;padding:10px;border-radius:8px;border:1px solid #e6edf0;font-size:14px}
    form.contact-form textarea{min-height:120px;resize:vertical}
    .contact-btn{background:var(--primary);color:white;padding:10px 14px;border-radius:10px;border:0;cursor:pointer;font-weight:600}
    .contact-muted{color:var(--muted);font-size:13px}

    @media (max-width:900px){
      .contact-grid{grid-template-columns:1fr}
      .contact-hero{flex-direction:column;align-items:flex-start}
    }

    .contact-small{font-size:13px;color:var(--muted)}
    .contact-row{display:flex;gap:8px}
  </style>
</head>
<body>
  <div class="contact-container">
    <div class="contact-hero">
      <div class="contact-logo">KS</div>
      <div>
        <h1>Liên hệ - The Grand Elegance Hotel</h1>
        <p>Chúng tôi luôn sẵn sàng hỗ trợ 24/7 — đặt phòng, sự kiện, hợp tác.</p>
      </div>
    </div>

    <div class="contact-grid">
      <!-- LEFT -->
      <div>
        <div class="contact-card contact-about">
          <h2>Giới thiệu ngắn</h2>
          <p class="contact-small">The Grand Elegance Hotel tọa lạc tại trung tâm thành phố, cung cấp phòng nghỉ cao cấp, dịch vụ tổ chức sự kiện, ẩm thực và chăm sóc khách hàng tận tâm.</p>

          <div class="contact-info">
            <div class="contact-info-row">
              <div class="contact-info-icon">📍</div>
              <div class="contact-info-text">
                <span class="label">Địa chỉ</span>
                <div>The Grand Elegance Hotel, 32 Lò Sũ, Hoàn Kiếm, Hà Nội</div>
              </div>
            </div>

            <div class="contact-info-row">
              <div class="contact-info-icon">📞</div>
              <div class="contact-info-text">
                <span class="label">Điện thoại</span>
                <div>+84 24 3935 1632</div>
              </div>
            </div>

            <div class="contact-info-row">
              <div class="contact-info-icon">✉️</div>
              <div class="contact-info-text">
                <span class="label">Email</span>
                <div><a href="mailto:info@theelghotel.com">info@theelghotel.com</a></div>
              </div>
            </div>

            <div class="contact-info-row">
              <div class="contact-info-icon">🕘</div>
              <div class="contact-info-text">
                <span class="label">Giờ làm việc</span>
                <div>24/7 - Hỗ trợ trực tiếp</div>
              </div>
            </div>

            <div>
              <span class="label">Mạng xã hội</span>
              <div class="contact-socials">
                <a href="https://www.facebook.com/" target="_blank">Facebook</a>
                <a href="https://www.instagram.com/" target="_blank">Instagram</a>
                <a href="https://zalo.me/" target="_blank">Zalo</a>
                <a href="mailto:info@theelghotel.com">Mail</a>
              </div>
            </div>
          </div>
        </div>

        <div class="contact-card" style="margin-top:14px">
          <h3>Bản đồ</h3>
          <div class="contact-map">
            <!-- 📌 Ghim đỏ tại Hanoi Elegance Diamond Hotel -->
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.116270659955!2d105.85434857594257!3d21.02851178062019!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135abfd17c674c9%3A0x9f63e2b3e77a4d3d!2sHanoi%20Elegance%20Diamond%20Hotel!5e0!3m2!1svi!2s!4v1695712345678" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div>
        </div>
      </div>

      <!-- RIGHT -->
      <div>
        <div class="contact-card">
          <h3>Liên hệ nhanh</h3>
          <p class="contact-muted">Gửi tin nhắn cho chúng tôi — chúng tôi sẽ phản hồi trong vòng vài giờ.</p>

          <form id="contactForm" class="contact-form" onsubmit="return handleSubmit(event)">
            <label class="contact-small">Họ &amp; tên</label>
            <input type="text" id="name" placeholder="Nguyễn Văn A" required />

            <label class="contact-small">Email</label>
            <input type="email" id="email" placeholder="you@example.com" required />

            <label class="contact-small">Số điện thoại (tuỳ chọn)</label>
            <input type="text" id="phone" placeholder="+84 9x xxx xxxx" />

            <label class="contact-small">Nội dung</label>
            <textarea id="message" placeholder="Viết nội dung liên hệ..." required></textarea>

            <div class="contact-row" style="justify-content:space-between;align-items:center">
              <div class="contact-small">Hoặc gọi: <strong>+84 24 3935 1632</strong></div>
              <button class="contact-btn" type="submit">Gửi liên hệ</button>
            </div>

            <div id="formFeedback" class="contact-small contact-muted" style="margin-top:8px;display:none"></div>
          </form>
        </div>

        <div class="contact-card" style="margin-top:14px">
          <h3>Thông tin nhanh</h3>
          <p class="contact-small"><strong>Check-in:</strong> 14:00 | <strong>Check-out:</strong> 12:00</p>
          <p class="contact-small"><strong>Tiện nghi:</strong> Wifi, Nhà hàng, Hồ bơi, Phòng hội thảo</p>
          <p class="contact-small">Cần hỗ trợ đặt phòng nhanh? <a href="tel:+842439351632">Gọi ngay</a></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    function handleSubmit(e){
      e.preventDefault();
      const name = document.getElementById('name').value.trim();
      const email = document.getElementById('email').value.trim();
      const message = document.getElementById('message').value.trim();
      const feedback = document.getElementById('formFeedback');

      if(!name || !email || !message){
        feedback.style.display = 'block';
        feedback.style.color = 'crimson';
        feedback.textContent = 'Vui lòng điền đủ họ tên, email và nội dung.';
        return false;
      }
      const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
      if(!emailRegex.test(email)){
        feedback.style.display = 'block';
        feedback.style.color = 'crimson';
        feedback.textContent = 'Email không hợp lệ.';
        return false;
      }
      feedback.style.display = 'block';
      feedback.style.color = 'green';
      feedback.textContent = 'Gửi thành công! Chúng tôi sẽ liên hệ sớm.';
      setTimeout(()=>{document.getElementById('contactForm').reset();feedback.style.display='none';},2000);
      return false;
    }
  </script>
</body>
</html>
<?php
include 'footer.php';
?>
 