# Siine OTP Gateway (API)

This repository contains a Laravel-based OTP (One-Time Password) API service. The service allows for generating and verifying OTPs for user authentication. It includes integration with Infobip for sending OTPs via SMS.

## Features

- **Generate OTP**: Send an OTP to a phone number with rate limiting and validation.
- **Verify OTP**: Verify the OTP entered by the user against the stored OTP.
- **Phone Number Validation**: Validates phone numbers against a regex pattern.
- **Infobip Integration**: Sends OTPs via SMS using Infobip's API.

## API Endpoints

### Generate OTP

**Endpoint**: `POST /api/otp/generate`

**Description**: Generates and sends a 4-digit OTP to the specified phone number.

**Request Parameters**:
- `phone`: (string) The phone number to which the OTP will be sent. Must start with `05`, `06`, or `07`, followed by 8 digits.

**Request Example**:

```bash
curl -X POST https://your-domain.com/api/otp/generate \
-H "Content-Type: application/json" \
-d '{"phone": "0555555555"}'
```

### Verify OTP
**Endpoint**: `POST /api/otp/verify`

**Description**: Verifies the OTP entered by the user. This endpoint checks if the provided OTP matches the one stored in the database and if it has not expired.

**Request Parameters**:
- `phone`: (string) The phone number to which the OTP will be sent. Must start with `05`, `06`, or `07`, followed by 8 digits.
- otp: (string) The OTP code to be verified. It must be a 4-digit code.

**Request Example**:

```bash
curl -X POST https://your-domain.com/api/otp/verify \
-H "Content-Type: application/json" \
-d '{"phone": 0555555555", "otp": "1234"}'
```

