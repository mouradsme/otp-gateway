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

**Success Response**
```json
{
    "message": "OTP_SENT"
}
```
- Description: This response indicates that the OTP was successfully generated and sent to the provided phone number.

**Validation Error Response**
```json
{
    "message": "VALIDATION_ERROR",
    "status": "error",
    "errors": {
        "phone": [
            "The phone format is invalid."
        ]
    }
}
```
- Description: This response indicates that the request failed validation. The errors field contains details about why the phone number is invalid. Adjust the validation error messages based on your specific requirements.

**Rate Limit Exceeded Response**
```json
{
    "message": "RATE_LIMIT_EXCEEDED"
}
```
- Description: This response indicates that the rate limit for OTP requests has been exceeded. The client should wait before making another request.


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

**Success Response**
```json
{
    "message": "OTP_VERIFIED"
}
```
- Description: This response indicates that the provided OTP was successfully verified, and the OTP record has been deleted from the database.

**Validation Error**
```json
{
    "message": "VALIDATION_ERROR",
    "status": "error",
    "errors": {
        "phone": [
            "The phone format is invalid."
        ],
        "otp": [
            "The OTP must be exactly 4 digits."
        ]
    }
}
```
- Description: This response indicates that the request failed validation. The errors field provides details about which parameters failed validation.

**Invalid OTP Response**
```json
{
    "message": "INVALID_INPUT"
}
```
-Description: This response indicates that the OTP verification failed because no OTP record was found for the provided phone number.

**Invalid or Expired OTP Response**
```json
{
    "message": "INVALID_OR_EXPIRED"
}
```
- Description: This response indicates that the OTP provided does not match the stored OTP or the OTP has expired. The client should request a new OTP if needed.

