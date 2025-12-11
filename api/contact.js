import nodemailer from "nodemailer";

export default async function handler(req, res) {
  // Only allow POST requests
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Method not allowed" });
  }

  try {
    const { name, email, message } = req.body;

    // Validate input
    if (!name || !email || !message) {
      return res.status(400).json({ error: "Missing required fields" });
    }

    // Validate email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return res.status(400).json({ error: "Invalid email format" });
    }

    // Create a transporter using Gmail
    // Note: You need to set up environment variables on Vercel
    const transporter = nodemailer.createTransport({
      service: "gmail",
      auth: {
        user: process.env.GMAIL_USER,
        pass: process.env.GMAIL_PASSWORD,
      },
    });

    // Email content
    const mailOptions = {
      from: process.env.GMAIL_USER,
      to: process.env.GMAIL_USER,
      replyTo: email,
      subject: `New Contact from NESTHETIC Website: ${name}`,
      html: `
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> ${name}</p>
        <p><strong>Email:</strong> ${email}</p>
        <p><strong>Message:</strong></p>
        <p>${message.replace(/\n/g, "<br>")}</p>
        <hr>
        <p style="color: #888; font-size: 12px;">Sent from NESTHETIC Website Contact Form</p>
      `,
      text: `
Name: ${name}
Email: ${email}

Message:
${message}

---
Sent from NESTHETIC Website Contact Form
      `,
    };

    // Send email
    await transporter.sendMail(mailOptions);

    return res
      .status(200)
      .json({ success: true, message: "Email sent successfully" });
  } catch (error) {
    console.error("Error sending email:", error);
    return res
      .status(500)
      .json({ error: "Failed to send email", details: error.message });
  }
}
